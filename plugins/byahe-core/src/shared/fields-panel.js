/**
 * Inspector panels generated from a block's field schema (inc/schemas/*.json):
 * one text control per field, and editable rows for each list.
 */
import { __, sprintf } from '@wordpress/i18n';
import {
	Button,
	Flex,
	FlexItem,
	PanelBody,
	SelectControl,
	TextControl,
} from '@wordpress/components';

/**
 * @param {Object}                   props
 * @param {Object}                   props.schema   Field schema.
 * @param {Object}                   props.values   Saved values (overrides of the defaults).
 * @param {(values: Object) => void} props.onChange Called with the next values object.
 */
export default function FieldsPanel( { schema, values = {}, onChange } ) {
	const fields = schema.fields ?? {};
	const lists = schema.lists ?? {};

	const setField = ( key, value ) =>
		onChange( { ...values, [ key ]: value } );
	const rowsOf = ( key ) => values[ key ] ?? lists[ key ].default ?? [];
	const setRows = ( key, rows ) => onChange( { ...values, [ key ]: rows } );

	return (
		<>
			{ Object.keys( fields ).length > 0 && (
				<PanelBody title={ __( 'Content', 'byahe-core' ) }>
					{ Object.entries( fields ).map( ( [ key, def ] ) => (
						<TextControl
							key={ key }
							label={ def.label }
							value={ values[ key ] ?? def.default ?? '' }
							onChange={ ( value ) => setField( key, value ) }
							__next40pxDefaultSize
							__nextHasNoMarginBottom
						/>
					) ) }
				</PanelBody>
			) }
			{ Object.entries( lists ).map( ( [ key, def ] ) => {
				const rows = rowsOf( key );
				const columns = Object.entries( def.columns ?? {} );
				const blank = Object.fromEntries(
					columns.map( ( [ column ] ) => [ column, '' ] )
				);
				const update = ( index, column, value ) =>
					setRows(
						key,
						rows.map( ( row, i ) =>
							i === index ? { ...row, [ column ]: value } : row
						)
					);
				return (
					<PanelBody
						key={ key }
						title={ def.label }
						initialOpen={ false }
					>
						{ rows.map( ( row, index ) => (
							<fieldset
								key={ index }
								className="byahe-fields-row"
							>
								<legend>
									{ sprintf(
										/* translators: %d: row number. */
										__( 'Row %d', 'byahe-core' ),
										index + 1
									) }
								</legend>
								{ columns.map( ( [ column, col ] ) =>
									col.options ? (
										<SelectControl
											key={ column }
											label={ col.label }
											value={ row[ column ] ?? '' }
											options={ col.options.map(
												( option ) => ( {
													label: option,
													value: option,
												} )
											) }
											onChange={ ( value ) =>
												update( index, column, value )
											}
											__next40pxDefaultSize
											__nextHasNoMarginBottom
										/>
									) : (
										<TextControl
											key={ column }
											label={ col.label }
											value={ row[ column ] ?? '' }
											onChange={ ( value ) =>
												update( index, column, value )
											}
											__next40pxDefaultSize
											__nextHasNoMarginBottom
										/>
									)
								) }
								<Button
									variant="link"
									isDestructive
									onClick={ () =>
										setRows(
											key,
											rows.filter(
												( _, i ) => i !== index
											)
										)
									}
								>
									{ __( 'Remove row', 'byahe-core' ) }
								</Button>
							</fieldset>
						) ) }
						<Flex>
							<FlexItem>
								<Button
									variant="secondary"
									onClick={ () =>
										setRows( key, [ ...rows, blank ] )
									}
								>
									{ __( 'Add row', 'byahe-core' ) }
								</Button>
							</FlexItem>
							{ values[ key ] && (
								<FlexItem>
									<Button
										variant="tertiary"
										onClick={ () => {
											const next = { ...values };
											delete next[ key ];
											onChange( next );
										} }
									>
										{ __(
											'Reset to defaults',
											'byahe-core'
										) }
									</Button>
								</FlexItem>
							) }
						</Flex>
					</PanelBody>
				);
			} ) }
		</>
	);
}
