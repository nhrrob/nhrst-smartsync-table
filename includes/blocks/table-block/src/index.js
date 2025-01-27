import { registerBlockType } from '@wordpress/blocks';
import { useBlockProps, InspectorControls } from '@wordpress/block-editor';
import { PanelBody, ToggleControl } from '@wordpress/components';
import { useState, useEffect } from '@wordpress/element';
import './style.scss';

registerBlockType('nhrst-smartsync-table/table-block', {
    edit: ({ attributes, setAttributes }) => {
        const [apiData, setApiData] = useState(null);
        const blockProps = useBlockProps();

        useEffect(() => {
            fetchApiData();
        }, []);

        const fetchApiData = async () => {
            try {
                const response = await fetch(
                    `${nhrstSmartSyncTableCommonObj.ajax_url}?action=nhrst_get_table_data&nonce=${nhrstSmartSyncTableCommonObj.nonce}`
                );
                const result = await response.json();
                if (result.success) {
                    setApiData(result.data);
                }
            } catch (error) {
                console.error('Error fetching data:', error);
            }
        };

        return (
            <div {...blockProps}>
                <InspectorControls>
                    <PanelBody title="Column Settings">
                        {Object.keys(attributes.showColumns).map((column) => (
                            <ToggleControl
                                key={column}
                                label={`Show ${column} column`}
                                checked={attributes.showColumns[column]}
                                onChange={(value) => 
                                    setAttributes({
                                        showColumns: {
                                            ...attributes.showColumns,
                                            [column]: value
                                        }
                                    })
                                }
                            />
                        ))}
                    </PanelBody>
                </InspectorControls>
                
                {apiData ? (
                    <table className="nhrst-smartsync-table-table-block">
                        <thead>
                            <tr>
                                {Object.entries(attributes.showColumns).map(([column, show]) => 
                                    show && <th key={column}>{column}</th>
                                )}
                            </tr>
                        </thead>
                        <tbody>
                            {/* Render data rows */}
                        </tbody>
                    </table>
                ) : (
                    <p>Loading data...</p>
                )}
            </div>
        );
    },
    save: () => null
});