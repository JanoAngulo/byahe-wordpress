import { __ } from '@wordpress/i18n';
import {
	InnerBlocks,
	InspectorControls,
	useBlockProps,
} from '@wordpress/block-editor';
import { PanelBody, SelectControl, ToggleControl } from '@wordpress/components';

const TEMPLATE = [
	[ 'core/paragraph', { className: 'tier__desc' } ],
	[ 'core/list', { className: 'tier__list' } ],
	[ 'core/buttons', {}, [ [ 'core/button' ] ] ],
];

/** Pricing data printed for the editor by Byahe\Core\editor_pricing_data(). */
const tiers = () => window.byahePricing?.tiers ?? {};

export default function Edit( { attributes, setAttributes } ) {
	const { tier, featured } = attributes;
	const data = tiers()[ tier ] ?? {};
	const blockProps = useBlockProps( {
		className: featured
			? 'card-hard tier card-hard-lg bg-yellow!'
			: 'card-hard tier',
	} );

	return (
		<div { ...blockProps }>
			<InspectorControls>
				<PanelBody title={ __( 'Plan', 'byahe-core' ) }>
					<SelectControl
						label={ __( 'Plan', 'byahe-core' ) }
						help={ __(
							'Prices are set once in the BYAHE pricing data.',
							'byahe-core'
						) }
						value={ tier }
						options={ Object.entries( tiers() ).map(
							( [ value, t ] ) => ( { value, label: t.name } )
						) }
						onChange={ ( next ) => setAttributes( { tier: next } ) }
						__next40pxDefaultSize
						__nextHasNoMarginBottom
					/>
					<ToggleControl
						label={ __( 'Highlight this plan', 'byahe-core' ) }
						checked={ featured }
						onChange={ ( next ) =>
							setAttributes( { featured: next } )
						}
						__nextHasNoMarginBottom
					/>
				</PanelBody>
			</InspectorControls>
			<h3 className="tier__name">{ data.name ?? tier }</h3>
			{ data.badge && (
				<span className="pill pill-pending tier__badge">
					{ data.badge }
				</span>
			) }
			<p className="tier__price">
				<span className={ data.isText ? 'price-text' : 'price num' }>
					{ data.price }
				</span>{ ' ' }
				<span className="price-period">{ data.period }</span>
			</p>
			<InnerBlocks template={ TEMPLATE } />
		</div>
	);
}
