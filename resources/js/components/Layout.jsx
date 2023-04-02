import { Fragment, useEffect, useState } from "react";
import { AnimatePresence, motion } from "framer-motion";
import Loading from "./Loading";
import Soon from "./Soon";
import Dashboard from "./Dashboard";

const Layout = () => {
	const [loading, setLoading] = useState(true);

	useEffect(() => {
		setTimeout(() => {
			setLoading(false);
		}, 2500);
	}, []);

	return (
		<Fragment>
			{loading ? (
				<Loading />
			) : (
				<Fragment>
					<Soon />
					<Dashboard />
				</Fragment>
			)}
		</Fragment>
	);
};

export default Layout;
