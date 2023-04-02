import React from "react";

const Soon = () => {
	return (
		<div className="fixed inset-0 z-50 block">
			<div className="fixed inset-0 bg-zinc-400/25 backdrop-blur-sm dark:bg-black/40 opacity-100"></div>
			<div className="fixed inset-0 overflow-hidden px-4 lg:px-8">
				<div className="relative flex justify-center items-center h-screen">
					<h1 className="text-4xl md:text-5xl font-extrabold text-center lg:text-7xl 2xl:text-8xl">
						<span className="text-transparent bg-gradient-to-br bg-clip-text from-teal-500 via-indigo-500 to-sky-500 dark:from-teal-200 dark:via-indigo-300 dark:to-sky-500">
							Coming
						</span>
						<span className="text-transparent bg-gradient-to-tr bg-clip-text from-primary-500 via-pink-500 to-red-500 dark:from-sky-300 dark:via-pink-300 dark:to-red-500">
							Soon
						</span>
					</h1>
				</div>
			</div>
		</div>
	);
};

export default Soon;
