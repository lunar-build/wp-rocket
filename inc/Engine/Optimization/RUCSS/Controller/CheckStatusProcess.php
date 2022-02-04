<?php
declare(strict_types=1);

namespace WP_Rocket\Engine\Optimization\RUCSS\Controller;

use WP_Rocket\Engine\Cache\Purge;
use WP_Rocket\Engine\Optimization\RUCSS\Database\Queries\UsedCSS as UsedCSS_Query;
use WP_Rocket\Engine\Optimization\RUCSS\Frontend\APIClient;
use WP_Rocket\Logger\Logger;
use WP_Rocket_WP_Background_Process;

class CheckStatusProcess extends WP_Rocket_WP_Background_Process {

	/**
	 * UsedCss Query instance.
	 *
	 * @var UsedCSS_Query
	 */
	private $used_css_query;

	/**
	 * APIClient instance
	 *
	 * @var APIClient
	 */
	private $api;

	/**
	 * Purge instance
	 *
	 * @var Purge
	 */
	private $purge;

	public function __construct( UsedCSS_Query $used_css_query, APIClient $api, Purge $purge ) {
		parent::__construct();

		$this->used_css_query = $used_css_query;
		$this->api            = $api;
		$this->purge          = $purge;
	}

	/**
	 * @inheritDoc
	 */
	protected function task( $usedcss_row_id ) {
		$this->check_job_status( (int) $usedcss_row_id );

		return false;
	}

	/**
	 * Check job status by DB row ID.
	 *
	 * @param int $id DB Row ID.
	 *
	 * @return void
	 */
	private function check_job_status( int $id ) {
		Logger::debug( 'RUCSS: Start checking job status for row ID: ' . $id );

		$row_details = $this->used_css_query->get_item( $id );
		if ( ! $row_details ) {
			Logger::debug( 'RUCSS: Row ID not found ', compact( 'id' ) );

			// Nothing in DB, bailout.
			return;
		}

		// Send the request to get the job status from SaaS.
		$job_details = $this->api->get_queue_job_status( $row_details->job_id, $row_details->queue_name );
		if (
			200 !== $job_details['code']
			||
			empty( $job_details['contents'] )
			||
			empty( $job_details['contents']['shakedCSS'] )
		) {
			Logger::debug( 'RUCSS: Job status failed for url: ' . $row_details->url, $job_details );

			// Failure, check the retries number.
			if ( $row_details->retries >= 3 ) {
				Logger::debug( 'RUCSS: Job failed 3 times for url: ' . $row_details->url );

				$params = [
					'status'     => 'failed',
					'queue_name' => '',
					'job_id'     => '',
				];
				$this->used_css_query->update_item( $id, $params );

				return;
			}

			// Increment the retries number with 1.
			$this->used_css_query->increment_retries( $id, $row_details->retries );
			//@Todo: Maybe we can add this row to the async job to get the status before the next cron

			return;
		}

		//Everything is fine, save the usedcss into DB, change status to completed and reset queue_name and job_id.
		Logger::debug( 'RUCSS: Save used CSS for url: ' . $row_details->url );

		$params = [
			'css'        => $job_details['contents']['shakedCSS'],
			'status'     => 'completed',
			'queue_name' => '',
			'job_id'     => '',
		];
		$this->used_css_query->update_item( $id, $params );

		//Flush cache for this url.
		Logger::debug( 'RUCSS: Purge the cache for url: ' . $row_details->url );
		$this->purge->purge_url( $row_details->url );

		do_action( 'rucss_complete_job_status', $row_details->url, $job_details );

	}
}
