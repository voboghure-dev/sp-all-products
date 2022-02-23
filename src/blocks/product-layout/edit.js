import { __ } from "@wordpress/i18n";
import {
	ContrastChecker,
	InspectorControls,
	PanelColorSettings,
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
							max={12}
						/>
						<RangeControl
							label="Rows"
							value={gridRows}
							onChange={(gridRows) => setAttributes({ gridRows })}
							min={1}
							max={10}
						/>
						<TextControl
							label={__("Column spacing", "sp-all-products")}
							help={__("Write only number without px.", "sp-all-products")}
							onChange={(gridGap) => setAttributes({ gridGap })}
							value={gridGap}
						/>
					</PanelBody>
					{/* <PanelColorSettings
						title={__("Color Settings", "sp-all-products")}
						icon="art"
						initialOpen={false}
						colorSettings={[
							{
								value: blockColor,
								onChange: (blockColor) => setAttributes({ blockColor }),
								label: __("Font Color", "sp-all-products"),
							},
							{
								value: blockBackground,
								onChange: (blockBackground) =>
									setAttributes({ blockBackground }),
								label: __("Background Color", "sp-all-products"),
							},
						]}
					>
						<ContrastChecker
							isLargeText="false"
							textColor={blockColor}
							backgroundColor={blockBackground}
						/>
					</PanelColorSettings> */}
				</Panel>
			</InspectorControls>

			<div {...useBlockProps()}>
				<BlockControls>
					<ToolbarButton
						icon={edit}
						label="Edit"
						onClick={() => alert("Editing")}
					/>
				</BlockControls>

				<div className="wrapper">
					<div className="card">
						<div className="arivalDate">Arived in Dec 27</div>
						<div className="imageArea">
							<img src={shoe} alt="" />
						</div>
						<div className="discount">59% Off</div>
						<div className="productName">
							Men's Annapolis Desert Lather Chukka
						</div>
						<div className="itemPrice">
							<span>$94 </span>$50
						</div>
						<div className="colorCercle">
							<span className="cercle"></span>
							<span className="cercle red"></span>
							<span className="cercle green"></span>
							<span className="cercle blue"></span>
						</div>
					</div>
				</div>
			</div>
		</>
	);
}
