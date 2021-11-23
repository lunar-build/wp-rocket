<?php

declare( strict_types=1 );

$expected_html = <<<HTML
<div class="notice notice-warning is-dismissible">
<p><strong>
We have detected that Autoptimize's Aggregate Inline CSS feature is enabled. WP Rocket's Load CSS Asynchronously will not be applied to the file it creates. We suggest disabling it to take full advantage of WP Rocket's Load CSS Asynchronously Execution.
</strong></p>
<p><a class="rocket-dismiss" href="http://example.org/wp-admin/admin-post.php?action=rocket_ignore&amp;box=warn_when_aggregate_inline_css_and_cpcss_active&amp;_wpnonce=123456">
Dismiss this notice.</a></p>
</div>
HTML;

return [
//	'shouldAddNoticeWhenAutoptimizeAggregateInlineCssOnAndCPCSSActivated' => [
//		'config'   => [
//			'cpcssActive'                => true,
//			'autoptimizeAggregateInlineCSSActive' => 'on',
//			'dismissed'                    => false,
//		],
//		'expected' => $expected_html
//	],
//
//	'shouldSkipWhenAutoptimizeAggregateInlineCssOffAndCPCSSNotActivated' => [
//		'config'   => [
//			'cpcssActive'                => false,
//			'autoptimizeAggregateInlineCSSActive' => 'off',
//			'dismissed'                    => false,
//		],
//		'expected' => '',
//	],
//
//	'shouldSkipWhenAutoptimizeAggregateInlineCssOffAndCPCSSActivated' => [
//		'config'   => [
//			'cpcssActive'                => true,
//			'autoptimizeAggregateInlineCSSActive' => 'off',
//			'dismissed'                    => false,
//		],
//		'expected' => '',
//	],
//
//	'shouldSkipWhenAutoptimizeAggregateInlineCssOnAndCPCSSNotActivated' => [
//		'config'   => [
//			'cpcssActive'                => false,
//			'autoptimizeAggregateInlineCSSActive' => 'on',
//			'dismissed'                    => false,
//		],
//		'expected' => '',
//	],
//
//	'shouldSkipWhenUserHasDismissedNotice' => [
//		'config'   => [
//			'cpcssActive'                => true,
//			'autoptimizeAggregateInlineCSSActive' => 'on',
//			'dismissed'                    => true,
//		],
//		'expected' => '',
//	],
//
//	'shouldClearDismissalWhenUserDeactivatesCPCSS' => [
//		'config'   => [
//			'cpcssActive'                => false,
//			'autoptimizeAggregateInlineCSSActive' => 'on',
//			'dismissed'                    => true,
//		],
//		'expected' => '',
//	],

	'shouldClearDismissalWhenUserDeactivatesAggregateInlineCss' => [
		'config'   => [
			'cpcssActive'                         => true,
			'autoptimizeAggregateInlineCSSActive' => 'off',
			'dismissed'                           => true,
		],
		'expected' => '',
	],
];
