import * as React from "react";
import type { RouteObject } from "react-router-dom";
import { Outlet } from "react-router-dom";

export const route: RouteObject = {
	path: "auth0",
	element: <Outlet />,
	children: [
		{
			path: "callback",
			lazy: async () => {
				const { Page } = await import("@/pages/auth/auth0/callback");
				return { Component: Page };
			},
		},
		{
			path: "sign-in",
			lazy: async () => {
				const { Page } = await import("@/pages/auth/auth0/sign-in");
				return { Component: Page };
			},
		},
	],
};
