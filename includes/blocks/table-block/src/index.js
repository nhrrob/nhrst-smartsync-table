import { registerBlockType } from '@wordpress/blocks';
import { useBlockProps, InspectorControls } from '@wordpress/block-editor';
import { PanelBody, ToggleControl } from '@wordpress/components';
import { Spinner } from '@wordpress/components';
import { useState, useEffect } from '@wordpress/element';
import { __ } from '@wordpress/i18n';
import './style.scss';

registerBlockType('nhrst-smartsync-table/table-block', {
    edit: ({ attributes, setAttributes }) => {
        const [apiData, setApiData] = useState(null);
        const [isLoading, setIsLoading] = useState(true);
        const [error, setError] = useState(null);
        const blockProps = useBlockProps();

        // Column mapping for display names
        const columnMapping = {
            'id': 'ID',
            'fname': 'First Name',
            'lname': 'Last Name',
            'email': 'Email',
            'date': 'Date'
        };

        useEffect(() => {
            fetchApiData();
        }, []);

        const fetchApiData = async () => {
            try {
                setIsLoading(true);
                setError(null);

                const response = await fetch(
                    `${nhrstSmartSyncTableCommonObj.ajax_url}?action=nhrst_get_table_data&nonce=${nhrstSmartSyncTableCommonObj.nonce}`
                );

                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }

                const result = await response.json();
                if (result.success && result.data) {
                    setApiData(result.data);
                } else {
                    throw new Error('Invalid data received from API');
                }
            } catch (error) {
                setError(error.message);
                console.error('Error fetching data:', error);
            } finally {
                setIsLoading(false);
            }
        };

        const headers = Object.keys(columnMapping);

        const renderTable = () => {
            if (!apiData?.data?.rows) {
                return <p>{__('No data available.', 'nhrst-smartsync-table')}</p>;
            }

            const rows = Object.values(apiData.data.rows);

            return (
                <div className="nhrst-table-block-table-wrapper">
                    <h3>{apiData.title}</h3>

                    <table className="nhrst-table-block-table">
                        <thead>
                            <tr>
                                {headers.map((header) => 
                                    attributes.showColumns[header] && (
                                        <th key={header}>
                                            {columnMapping[header]}
                                        </th>
                                    )
                                )}
                            </tr>
                        </thead>
                        <tbody>
                            {rows.map((row, rowIndex) => (
                                <tr key={rowIndex}>
                                    {headers.map((header) => 
                                        attributes.showColumns[header] && (
                                            <td key={`${rowIndex}-${header}`}>
                                                {header === 'date' 
                                                    ? formatDate(row[header])
                                                    : formatCellValue(row[header])
                                                }
                                            </td>
                                        )
                                    )}
                                </tr>
                            ))}
                        </tbody>
                    </table>
                    
                    <button 
                        className="components-button is-secondary"
                        onClick={fetchApiData}
                    >
                        {__('Refresh Data', 'nhrst-smartsync-table')}
                    </button>
                </div>
            );
        };

        const formatCellValue = (value) => {
            if (typeof value === 'number') {
                return value.toLocaleString();
            }
            if (value === null || value === undefined) {
                return '-';
            }
            return value.toString();
        };

        const formatDate = (timestamp) => {
            return new Date(timestamp * 1000).toLocaleDateString();
        };

        return (
            <div {...blockProps}>
                <InspectorControls>
                    <PanelBody 
                        title={__('Column Settings', 'nhrst-smartsync-table')}
                        initialOpen={true}
                    >
                        {headers?.map((header) => (
                            <ToggleControl
                                key={header}
                                label={columnMapping[header]}
                                checked={attributes.showColumns[header]}
                                onChange={(value) => 
                                    setAttributes({
                                        showColumns: {
                                            ...attributes.showColumns,
                                            [header]: value
                                        }
                                    })
                                }
                            />
                        ))}
                    </PanelBody>
                </InspectorControls>

                <div className="nhrst-table-block-wrapper">
                    {isLoading ? (
                        <div className="nhrst-api-loading">
                            <Spinner />
                            <p>{__('Loading data...', 'nhrst-smartsync-table')}</p>
                        </div>
                    ) : error ? (
                        <div className="nhrst-api-error">
                            <p>{__('Error loading data:', 'nhrst-smartsync-table')} {error}</p>
                            <button 
                                className="components-button is-secondary"
                                onClick={fetchApiData}
                            >
                                {__('Retry', 'nhrst-smartsync-table')}
                            </button>
                        </div>
                    ) : (
                        renderTable()
                    )}
                </div>
            </div>
        );
    },
    save: () => null
});