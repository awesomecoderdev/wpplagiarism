import React, { Fragment, useEffect } from "react";
import Dashboard from "./components/Dashboard";
import { AnimatePresence } from "framer-motion";

const Plugin = () => {
	return (
		<Fragment>
			<AnimatePresence>
				<Dashboard />
			</AnimatePresence>
		</Fragment>
	);
};

export default Plugin;
