import React, { Fragment, useEffect } from "react";
import Dashboard from "./components/Dashboard";

const Plugin = () => {
	return (
		<Fragment>
			<div className="relative -mt-8">
				<Dashboard />
			</div>
		</Fragment>
	);
};

export default Plugin;
