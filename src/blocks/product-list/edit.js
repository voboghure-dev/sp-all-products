// For import third party react component
import React from "react";
import Select from "react-select";

import { __ } from "@wordpress/i18n";
import {
	InspectorControls,
	useBlockProps,
	BlockControls,
} from "@wordpress/block-editor";
import {
	Panel,
	PanelBody,
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
		numberOfItem,
		gridGap,
		productCategories,
		productOffset,
		productOrderBy,
		productOrder,
		toggleOnSale,
		toggleCategory,
		toggleTitle,
		toggleDescription,
		toggleRating,
		togglePrice,
		toggleAddToCart,
	} = attributes;
	const [categories, setCategories] = useState(null);

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

	return (
		<>
			<InspectorControls>
				<Panel>
					<PanelBody title={__("Product List", "sp-all-products")}>
						<RangeControl
							label={__("Number of Item", "sp-all-products")}
							value={numberOfItem}
							onChange={(numberOfItem) => setAttributes({ numberOfItem })}
							min={1}
							max={50}
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
								{
									value: "rand",
									label: "Random",
								},
								{
									value: "date",
									label: "Date",
								},
								{
									value: "modified",
									label: "Modified",
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
							label={__("On Sale", "sp-all-products")}
							help={
								toggleOnSale ? "Show On Sale badge." : "Hide On Sale badge."
							}
							checked={toggleOnSale}
							onChange={() => {
								setAttributes({ toggleOnSale: !toggleOnSale });
							}}
						/>
						<ToggleControl
							label={__("Category", "sp-all-products")}
							help={
								toggleCategory ? "Show Category." : "Hide Category."
							}
							checked={toggleCategory}
							onChange={() => {
								setAttributes({ toggleCategory: !toggleCategory });
							}}
						/>
						<ToggleControl
							label={__("Title", "sp-all-products")}
							help={
								toggleTitle ? "Show Title." : "Hide Title."
							}
							checked={toggleTitle}
							onChange={() => {
								setAttributes({ toggleTitle: !toggleTitle });
							}}
						/>
						<ToggleControl
							label={__("Description", "sp-all-products")}
							help={
								toggleDescription ? "Show Description." : "Hide Description."
							}
							checked={toggleDescription}
							onChange={() => {
								setAttributes({ toggleDescription: !toggleDescription });
							}}
						/>
						<ToggleControl
							label={__("Rating", "sp-all-products")}
							help={
								toggleRating ? "Show Rating." : "Hide Rating."
							}
							checked={toggleRating}
							onChange={() => {
								setAttributes({ toggleRating: !toggleRating });
							}}
						/>
						<ToggleControl
							label={__("Price", "sp-all-products")}
							help={
								togglePrice ? "Show Price." : "Hide Price."
							}
							checked={togglePrice}
							onChange={() => {
								setAttributes({ togglePrice: !togglePrice });
							}}
						/>
						<ToggleControl
							label={__("Add to Cart", "sp-all-products")}
							help={
								toggleAddToCart ? "Show Add to Cart." : "Hide Add to Cart."
							}
							checked={toggleAddToCart}
							onChange={() => {
								setAttributes({ toggleAddToCart: !toggleAddToCart });
							}}
						/>
					</PanelBody>
				</Panel>
			</InspectorControls>

			<div {...useBlockProps()}>
				<BlockControls>
					<ToolbarButton
						icon={edit}
						label={__("Edit", "sp-all-products")}
						onClick={() =>
							alert("It will hide layout view and open single product view")
						}
					/>
				</BlockControls>

				<ServerSideRender
					block="store-press/sp-all-products-list"
					attributes={attributes}
				/>
			</div>
		</>
	);
}
