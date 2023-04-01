import React, { Fragment } from "react";
import ReactDOM from "react-dom/client";
import Plugin from "./Plugin";

if (document.getElementById("wpwrap") != null) {
	const root = ReactDOM.createRoot(document.getElementById("wpwrap"));
	root.render(<Plugin />);
}
