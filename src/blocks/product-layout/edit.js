import { __ } from "@wordpress/i18n";
import { useBlockProps } from "@wordpress/block-editor";
import "./editor.scss";

import shoe from './shoe.png';

export default function Edit() {
	return (
		<div {...useBlockProps()}>
			<div className="wrapper">
				<div className="card">
					<div className="arivalDate">Arived in Dec 27</div>
					<div className="imageArea">
						<img src={shoe} alt="" />
					</div>
					<div className="discount">59% Off</div>
					<div className="productName">Men's Annapolis Desert Lather Chukka</div>
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
	);
}
