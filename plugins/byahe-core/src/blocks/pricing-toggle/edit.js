import { __ } from '@wordpress/i18n';
import { useBlockProps } from '@wordpress/block-editor';

export default function Edit() {
	return (
		<div { ...useBlockProps( { className: 'billing-toggle' } ) }>
			<span className="billing-label">
				{ __( 'Bill yearly', 'byahe-core' ) }
			</span>
			<span className="switch" aria-hidden="true" />
			<span className="pill pill-paid">
				{ __( '2 months libre', 'byahe-core' ) }
			</span>
		</div>
	);
}
