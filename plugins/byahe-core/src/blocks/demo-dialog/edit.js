import { __ } from '@wordpress/i18n';
import { useBlockProps } from '@wordpress/block-editor';
import { Notice } from '@wordpress/components';

export default function Edit() {
	return (
		<div { ...useBlockProps() }>
			<Notice status="info" isDismissible={ false }>
				{ __(
					'Demo notice dialog. Visitors see it when they click a button with the class opens-demo-dialog.',
					'byahe-core'
				) }
			</Notice>
		</div>
	);
}
