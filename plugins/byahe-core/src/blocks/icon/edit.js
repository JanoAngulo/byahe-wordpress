import { __ } from '@wordpress/i18n';
import { InspectorControls, useBlockProps } from '@wordpress/block-editor';
import { PanelBody, SelectControl } from '@wordpress/components';
import ServerSideRender from '@wordpress/server-side-render';
import icons from '../../../inc/icons.json';

export default function Edit( { attributes, setAttributes } ) {
	return (
		<div { ...useBlockProps() }>
			<InspectorControls>
				<PanelBody title={ __( 'Icon', 'byahe-core' ) }>
					<SelectControl
						label={ __( 'Icon', 'byahe-core' ) }
						value={ attributes.icon }
						options={ icons.map( ( name ) => ( {
							label: name,
							value: name,
						} ) ) }
						onChange={ ( icon ) => setAttributes( { icon } ) }
						__next40pxDefaultSize
						__nextHasNoMarginBottom
					/>
				</PanelBody>
			</InspectorControls>
			<ServerSideRender
				block="byahe/icon"
				attributes={ attributes }
				skipBlockSupportAttributes
			/>
		</div>
	);
}
