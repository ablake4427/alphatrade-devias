import * as React from "react";
import type { RouteObject } from "react-router-dom";
import { Outlet } from "react-router-dom";

export const route: RouteObject = {
	path: "cognito",
	element: <Outlet />,
	children: [
		{
			path: "callback",
			lazy: async () => {
				const { Page } = await import("@/pages/auth/cognito/callback");
				return { Component: Page };
			},
		},
	],
};
