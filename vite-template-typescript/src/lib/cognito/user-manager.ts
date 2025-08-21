"use client";

import { UserManager } from "oidc-client-ts";

import { paths } from "@/paths";
import { getAppUrl } from "@/lib/get-app-url";

// NOTE: Since this is loaded only on the browser, we can cache the client instance.
//  Why do I need to call this function every time I want to use the user manager?
//  Short answer, you don't need to, but you may not be using Cognito and this may throw an error if you don't
//  configure the Cognito credentials. So, to avoid that, we create the user manager only when we need it.

let userManager: UserManager | undefined;

export function createUserManager(): UserManager {
	if (userManager) {
		return userManager;
	}

	userManager = new UserManager({
		authority: import.meta.env.VITE_COGNITO_AUTHORITY!,
		client_id: import.meta.env.VITE_COGNITO_CLIENT_ID!,
		redirect_uri: new URL(paths.auth.cognito.callback, getAppUrl()).toString(),
		response_type: "code",
		scope: "email openid phone",
	});

	return userManager;
}
