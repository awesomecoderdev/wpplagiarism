import React, { Fragment, useEffect } from "react";
import { AnimatePresence } from "framer-motion";
import Layout from "./components/Layout";

const Plugin = () => {
	return (
		<Fragment>
			<AnimatePresence>
				<Layout />
			</AnimatePresence>
		</Fragment>
	);
};

export default Plugin;
