import React from "react";
import { AnimatePresence, motion } from "framer-motion";

const Loading = () => {
	return (
		<>
			<motion.div
				initial={{ opacity: 0, scale: 0.5 }}
				animate={{ opacity: 1, scale: 1 }}
				exit={{ opacity: 0, scale: 0 }}
				transition={{
					x: { type: "spring", stiffness: 300, damping: 30 },
					opacity: { duration: 0.2 },
				}}
				className="fixed inset-0 z-[999999999999] h-screen overflow-hidden duration-500 flex justify-center items-center"
			>
				<div className="fixed inset-0 overflow-hidden px-4 lg:px-8">
					<div className="relative flex justify-center items-center h-screen">
						<div className="animate-spin h-10 w-10 z-[99999999999]">
							<div className="h-full w-full border-4 border-t-teal-500 border-b-sky-500 rounded-[50%]"></div>
						</div>
					</div>
				</div>
			</motion.div>
		</>
	);
};

export default Loading;
