import { __ } from "@wordpress/i18n";
import {
	InspectorControls,
	RichText,
	useBlockProps,
	BlockControls,
} from "@wordpress/block-editor";
import {
	Panel,
	PanelBody,
	TextControl,
	ToggleControl,
	SelectControl,
	RangeControl,
	ToolbarButton,
} from "@wordpress/components";
import { edit } from "@wordpress/icons";
import ServerSideRender from "@wordpress/server-side-render";

import "./editor.scss";

import shoe from "./shoe.png";

export default function Edit({ attributes, setAttributes }) {
	const { gridColumns, gridRows, gridGap } = attributes;

	return (
		<>
			<InspectorControls>
				<Panel>
					<PanelBody
						title={__("Column & Row", "sp-all-products")}
						icon="grid-view"
					>
						<RangeControl
							label="Columns"
							value={gridColumns}
							onChange={(gridColumns) => setAttributes({ gridColumns })}
							min={1}
							max={8}
						/>
						<RangeControl
							label="Rows"
							value={gridRows}
							onChange={(gridRows) => setAttributes({ gridRows })}
							min={1}
							max={8}
						/>
						<TextControl
							label={__("Column spacing", "sp-all-products")}
							help={__("Write only number without px.", "sp-all-products")}
							onChange={(gridGap) => setAttributes({ gridGap })}
							value={gridGap}
						/>
					</PanelBody>
				</Panel>
			</InspectorControls>

			<div {...useBlockProps()}>
				<BlockControls>
					<ToolbarButton
						icon={edit}
						label="Edit"
						onClick={() =>
							alert("It will hide layout view and open single product view")
						}
					/>
				</BlockControls>

				<ServerSideRender
					block="store-press/sp-all-products"
					attributes={attributes}
				/>
			</div>
		</>
	);
}
