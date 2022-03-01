// For import third party react component
import React from "react";
import Select from "react-select";

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
	__experimentalNumberControl as NumberControl,
} from "@wordpress/components";
import { useState, useEffect } from "@wordpress/element";
import { edit } from "@wordpress/icons";
import ServerSideRender from "@wordpress/server-side-render";

import "./editor.scss";

export default function Edit({ attributes, setAttributes }) {
	const {
		layout,
		gridColumns,
		gridRows,
		gridGap,
		productCategories,
		productOffset,
		productOrderBy,
		productOrder,
		toggleOnSale,
		toggleCategory,
		toggleTitle,
		toggleRating,
		togglePrice,
		toggleAddToCart,
	} = attributes;
	const [categories, setCategories] = useState(null);
	const [onSale, setOnSale] = useState(toggleOnSale);

	const apiFetch = wp.apiFetch;
	const { addQueryArgs } = wp.url;

	useEffect(() => {
		apiFetch({
			path: addQueryArgs(`wc/store/products/categories`),
		}).then((result) => {
			let data = result.map((item) => ({
				value: item.id,
				label: item.name,
			}));
			setCategories(data);
		});
	}, []);

	// console.log(categories);

	return (
		<>
			<InspectorControls>
				<Panel>
					<PanelBody title={__("Layout Settings", "sp-all-products")}>
						<SelectControl
							label={__("Type", "sp-all-products")}
							value={layout}
							onChange={(layout) => setAttributes({ layout })}
							options={[
								{
									value: "grid",
									label: "Grid",
								},
								{
									value: "list",
									label: "List",
								},
							]}
						/>
					</PanelBody>
					<PanelBody title={__("Product Grid", "sp-all-products")}>
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
						<NumberControl
							label={__("Column spacing", "sp-all-products")}
							onChange={(gridGap) => setAttributes({ gridGap })}
							value={gridGap}
						/>
					</PanelBody>
					<PanelBody title={__("Product Settings", "sp-all-products")}>
						<Select
							value={productCategories ? JSON.parse(productCategories) : ""}
							onChange={(productCategories) => {
								let data = JSON.stringify(productCategories);
								setAttributes({ productCategories: data });
							}}
							options={categories}
							isMulti="true"
							menuPortalTarget={document.body}
						/>
						<NumberControl
							label={__("Offset", "sp-all-products")}
							onChange={(productOffset) => setAttributes({ productOffset })}
							value={productOffset}
						/>
						<SelectControl
							label={__("Order by", "sp-all-products")}
							value={productOrderBy}
							onChange={(productOrderBy) => setAttributes({ productOrderBy })}
							options={[
								{
									value: "id",
									label: "ID",
								},
								{
									value: "title",
									label: "Title",
								},
								{
									value: "name",
									label: "Name",
								},
							]}
						/>
						<SelectControl
							label={__("Order", "sp-all-products")}
							value={productOrder}
							onChange={(productOrder) => setAttributes({ productOrder })}
							options={[
								{
									value: "ASC",
									label: "ASC",
								},
								{
									value: "DESC",
									label: "DESC",
								},
							]}
						/>
					</PanelBody>
					<PanelBody title={__("Content Settings", "sp-all-products")}>
						<ToggleControl
							label="On Sale"
							help={
								toggleOnSale ? "Show On Sale badge." : "Hide On Sale badge."
							}
							checked={toggleOnSale}
							onChange={() => {
								setAttributes({ toggleOnSale: !toggleOnSale });
							}}
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
