import { InspectorControls, useBlockProps } from '@wordpress/block-editor';
import ServerSideRender from '@wordpress/server-side-render';
import FieldsPanel from '../../shared/fields-panel.js';
import schema from '../../../inc/schemas/hero-dashboard.json';

export default function Edit( { attributes, setAttributes } ) {
	return (
		<div { ...useBlockProps() }>
			<InspectorControls>
				<FieldsPanel
					schema={ schema }
					values={ attributes.values }
					onChange={ ( values ) => setAttributes( { values } ) }
				/>
			</InspectorControls>
			<ServerSideRender
				block="byahe/hero-dashboard"
				attributes={ attributes }
				skipBlockSupportAttributes
			/>
		</div>
	);
}
