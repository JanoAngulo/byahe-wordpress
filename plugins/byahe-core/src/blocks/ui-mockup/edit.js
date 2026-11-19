import { __ } from '@wordpress/i18n';
import { InspectorControls, useBlockProps } from '@wordpress/block-editor';
import { PanelBody, SelectControl } from '@wordpress/components';
import ServerSideRender from '@wordpress/server-side-render';
import FieldsPanel from '../../shared/fields-panel.js';
import variants from '../../../inc/schemas/ui-mockup.json';

export default function Edit( { attributes, setAttributes } ) {
	const { variant, values } = attributes;
	const schema = variants[ variant ];

	return (
		<div { ...useBlockProps() }>
			<InspectorControls>
				<PanelBody title={ __( 'Mockup', 'byahe-core' ) }>
					<SelectControl
						label={ __( 'Screen', 'byahe-core' ) }
						value={ variant }
						options={ Object.entries( variants ).map(
							( [ value, def ] ) => ( {
								value,
								label: def.label,
							} )
						) }
						// Values belong to one screen, so switching starts from its defaults.
						onChange={ ( next ) =>
							setAttributes( { variant: next, values: {} } )
						}
						__next40pxDefaultSize
						__nextHasNoMarginBottom
					/>
				</PanelBody>
				{ schema && (
					<FieldsPanel
						schema={ schema }
						values={ values }
						onChange={ ( next ) =>
							setAttributes( { values: next } )
						}
					/>
				) }
			</InspectorControls>
			<ServerSideRender
				block="byahe/ui-mockup"
				attributes={ attributes }
				skipBlockSupportAttributes
			/>
		</div>
	);
}
