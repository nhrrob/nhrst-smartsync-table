import { registerBlockType } from '@wordpress/blocks';
import { useBlockProps, InspectorControls } from '@wordpress/block-editor';
import {
	PanelBody,
	ToggleControl,
	Spinner,
	SelectControl,
} from '@wordpress/components';
import { useState, useEffect } from '@wordpress/element';
import { __ } from '@wordpress/i18n';
import './style.scss';

const sortableColumns = [ 'id', 'fname', 'lname', 'email', 'date' ];

const orderOptions = [
	{ label: __( 'Ascending', 'nhrst-smartsync-table' ), value: 'asc' },
	{ label: __( 'Descending', 'nhrst-smartsync-table' ), value: 'desc' },
];

const Edit = ( { attributes, setAttributes } ) => {
	const [ apiData, setApiData ] = useState( null );
	const [ isLoading, setIsLoading ] = useState( true );
	const [ error, setError ] = useState( null );
	const blockProps = useBlockProps();

	// Column mapping for display names
	const columnMapping = {
		id: __( 'ID', 'nhrst-smartsync-table' ),
		fname: __( 'First Name', 'nhrst-smartsync-table' ),
		lname: __( 'Last Name', 'nhrst-smartsync-table' ),
		email: __( 'Email', 'nhrst-smartsync-table' ),
		date: __( 'Date', 'nhrst-smartsync-table' ),
	};

	useEffect( () => {
		fetchApiData();
	}, [] );

	const fetchApiData = async () => {
		try {
			setIsLoading( true );
			setError( null );

			const response = await fetch(
				`${ window.nhrstSmartSyncTableCommonObj.ajax_url }?action=nhrst_get_table_data&nonce=${ window.nhrstSmartSyncTableCommonObj.nonce }`
			);

			if ( ! response.ok ) {
				throw new Error(
					__( 'Network response was not ok', 'nhrst-smartsync-table' )
				);
			}

			const result = await response.json();
			if ( result.success && result.data ) {
				setApiData( result.data );
			} else {
				throw new Error(
					__(
						'Invalid data received from API',
						'nhrst-smartsync-table'
					)
				);
			}
		} catch ( fetchError ) {
			setError( fetchError.message );
			// eslint-disable-next-line no-console
			console.error(
				__( 'Error fetching data:', 'nhrst-smartsync-table' ),
				fetchError
			);
		} finally {
			setIsLoading( false );
		}
	};

	const headers = Object.keys( columnMapping );

	const handleSort = ( column ) => {
		if ( ! sortableColumns.includes( column ) ) {
			return;
		}

		// Toggle order direction if the same column is clicked
		const newDirection =
			attributes.orderBy === column && attributes.orderDirection === 'asc'
				? 'desc'
				: 'asc';

		setAttributes( {
			orderBy: column,
			orderDirection: newDirection,
		} );
	};

	const renderTable = () => {
		if ( ! apiData?.data?.rows ) {
			return (
				<p>{ __( 'No data available.', 'nhrst-smartsync-table' ) }</p>
			);
		}

		const rows = Object.values( apiData.data.rows );

		// rows.sort((a, b) => a.id - b.id);

		rows.sort( ( a, b ) => {
			const orderFactor = attributes.orderDirection === 'asc' ? 1 : -1;
			return (
				( a[ attributes.orderBy ] > b[ attributes.orderBy ] ? 1 : -1 ) *
				orderFactor
			);
		} );

		return (
			<div className="nhrst-table-block-table-wrapper">
				<h3>{ apiData.title }</h3>

				<table className="nhrst-table-block-table">
					<thead>
						<tr>
							{ headers.map(
								( header ) =>
									attributes.showColumns[ header ] && (
										<th
											key={ header }
											onClick={ () =>
												handleSort( header )
											}
											style={ { cursor: 'pointer' } }
										>
											{ columnMapping[ header ] }{ ' ' }
											{ attributes.orderBy === header && (
												<span>
													{ attributes.orderDirection ===
													'asc'
														? '↑'
														: '↓' }
												</span>
											) }
										</th>
									)
							) }
						</tr>
					</thead>
					<tbody>
						{ rows.map( ( row, rowIndex ) => (
							<tr key={ rowIndex }>
								{ headers.map(
									( header ) =>
										attributes.showColumns[ header ] && (
											<td
												key={ `${ rowIndex }-${ header }` }
											>
												{ header === 'date'
													? formatDate(
															row[ header ]
													  )
													: formatCellValue(
															row[ header ]
													  ) }
											</td>
										)
								) }
							</tr>
						) ) }
					</tbody>
				</table>
			</div>
		);
	};

	const formatCellValue = ( value ) => {
		if ( typeof value === 'number' ) {
			return value.toLocaleString();
		}
		if ( value === null || value === undefined ) {
			return '-';
		}
		return value.toString();
	};

	const formatDate = ( timestamp ) => {
		const dateFormat =
			window.nhrstSmartSyncTableCommonObj.date_format || 'Y-m-d';
		// return new Date(timestamp * 1000).toLocaleDateString();
		return new Date( timestamp * 1000 ).toLocaleDateString(
			undefined,
			mapDateFormat( dateFormat )
		);
	};

	const mapDateFormat = ( format ) => {
		const formatMap = {
			'F j, Y': {
				year: 'numeric',
				month: 'long',
				day: 'numeric',
			},
			'M d, Y': {
				year: 'numeric',
				month: 'short',
				day: '2-digit',
			},
			'd/m/Y': {
				day: '2-digit',
				month: '2-digit',
				year: 'numeric',
			},
			'm/d/Y': {
				month: '2-digit',
				day: '2-digit',
				year: 'numeric',
			},
			'Y-m-d': {
				year: 'numeric',
				month: '2-digit',
				day: '2-digit',
			},
		};
		return (
			formatMap[ format ] || {
				year: 'numeric',
				month: '2-digit',
				day: '2-digit',
			}
		);
	};

	return (
		<div { ...blockProps }>
			<InspectorControls>
				<PanelBody
					title={ __( 'Column Settings', 'nhrst-smartsync-table' ) }
					initialOpen={ true }
				>
					{ headers?.map( ( header ) => (
						<ToggleControl
							key={ header }
							label={ columnMapping[ header ] }
							checked={ attributes.showColumns[ header ] }
							onChange={ ( value ) =>
								setAttributes( {
									showColumns: {
										...attributes.showColumns,
										[ header ]: value,
									},
								} )
							}
						/>
					) ) }
				</PanelBody>

				<PanelBody
					title={ __( 'Table Sorting', 'nhrst-smartsync-table' ) }
					initialOpen={ true }
				>
					<SelectControl
						label={ __( 'Order By', 'nhrst-smartsync-table' ) }
						value={ attributes.orderBy }
						options={ sortableColumns.map( ( col ) => ( {
							label: columnMapping[ col ],
							value: col,
						} ) ) }
						onChange={ ( value ) =>
							setAttributes( { orderBy: value } )
						}
					/>
					<SelectControl
						label={ __(
							'Order Direction',
							'nhrst-smartsync-table'
						) }
						value={ attributes.orderDirection }
						options={ orderOptions }
						onChange={ ( value ) =>
							setAttributes( { orderDirection: value } )
						}
					/>
				</PanelBody>
			</InspectorControls>

			<div className="nhrst-table-block-wrapper">
				{ isLoading && (
					<div className="nhrst-api-loading">
						<Spinner />
						<p>
							{ __( 'Loading data…', 'nhrst-smartsync-table' ) }
						</p>
					</div>
				) }
				{ ! isLoading && error && (
					<div className="nhrst-api-error">
						<p>
							{ __(
								'Error loading data:',
								'nhrst-smartsync-table'
							) }{ ' ' }
							{ error }
						</p>
						<button
							className="components-button is-secondary"
							onClick={ fetchApiData }
						>
							{ __( 'Retry', 'nhrst-smartsync-table' ) }
						</button>
					</div>
				) }
				{ ! isLoading && ! error && renderTable() }
			</div>
		</div>
	);
};

registerBlockType( 'nhrst-smartsync-table/table-block', {
	edit: Edit,
	save: () => null,
} );
