const { registerBlockType } = wp.blocks;
const { RangeControl, ToggleControl } = wp.components;
const { InspectorControls } = wp.blockEditor;
const { useState, useEffect } = wp.element;

registerBlockType('nhrst-smartsync-table/table-block', {
    title: 'NHR SmartSync Table',
    icon: 'database',
    category: 'widgets',
    attributes: {
        showColumns: {
            type: 'object',
            default: {
                id: true,
                name: true,
                email: true
            }
        }
    },
    edit: (props) => {
        const { attributes, setAttributes } = props;
        const [apiData, setApiData] = useState(null);

        useEffect(() => {
            fetchApiData();
        }, []);

        const fetchApiData = () => {
            fetch(`${nhrstSmartSyncTableCommonObj.ajax_url}?action=nhrst_get_table_data&nonce=${nhrstSmartSyncTableCommonObj.nonce}`)
                .then(response => response.json())
                .then(result => {
                    if (result.success) {
                        setApiData(result.data);
                    }
                });
        };

        const renderTable = () => {
            if (!apiData) return <p>Loading...</p>;

            return (
                <table>
                    <thead>
                        <tr>
                            {attributes.showColumns.id && <th>ID</th>}
                            {attributes.showColumns.name && <th>Name</th>}
                            {attributes.showColumns.email && <th>Email</th>}
                        </tr>
                    </thead>
                    <tbody>
                        {/* Render table rows based on API data */}
                    </tbody>
                </table>
            );
        };

        return (
            <>
                <InspectorControls>
                    <div>
                        <ToggleControl
                            label="Show ID Column"
                            checked={attributes.showColumns.id}
                            onChange={(value) => setAttributes({
                                showColumns: {
                                    ...attributes.showColumns,
                                    id: value
                                }
                            })}
                        />
                        <ToggleControl
                            label="Show Name Column"
                            checked={attributes.showColumns.name}
                            onChange={(value) => setAttributes({
                                showColumns: {
                                    ...attributes.showColumns,
                                    name: value
                                }
                            })}
                        />
                        <ToggleControl
                            label="Show Email Column"
                            checked={attributes.showColumns.email}
                            onChange={(value) => setAttributes({
                                showColumns: {
                                    ...attributes.showColumns,
                                    email: value
                                }
                            })}
                        />
                    </div>
                </InspectorControls>
                {renderTable()}
            </>
        );
    },
    save: () => {
        return null; // Dynamic rendering on frontend
    }
});