import { __ } from '@wordpress/i18n';
import { InspectorControls, useBlockProps } from '@wordpress/block-editor';
import { PanelBody, TextControl } from '@wordpress/components';
import ServerSideRender from '@wordpress/server-side-render';

export default function Edit( { attributes, setAttributes } ) {
	return (
		<div { ...useBlockProps() }>
			<InspectorControls>
				<PanelBody title={ __( 'Table', 'byahe-core' ) }>
					<TextControl
						label={ __( 'Caption', 'byahe-core' ) }
						help={ __(
							'Fees come from the BYAHE pricing data.',
							'byahe-core'
						) }
						value={ attributes.caption }
						onChange={ ( caption ) => setAttributes( { caption } ) }
						__next40pxDefaultSize
						__nextHasNoMarginBottom
					/>
				</PanelBody>
			</InspectorControls>
			<ServerSideRender
				block="byahe/fee-table"
				attributes={ attributes }
				skipBlockSupportAttributes
			/>
		</div>
	);
}
