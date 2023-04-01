import React, { Fragment } from "react";
import ReactDOM from "react-dom/client";
import Plugin from "./Plugin";

if (document.getElementById("wpbody") != null) {
	const root = ReactDOM.createRoot(document.getElementById("wpbody"));
	root.render(<Plugin />);
}
