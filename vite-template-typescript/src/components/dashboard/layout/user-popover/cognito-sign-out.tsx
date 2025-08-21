"use client";

import * as React from "react";
import MenuItem from "@mui/material/MenuItem";

import { createUserManager } from "@/lib/cognito/user-manager";
import { logger } from "@/lib/default-logger";
import { getAppUrl } from "@/lib/get-app-url";
import { toast } from "@/components/core/toaster";

export function CognitoSignOut(): React.JSX.Element {
	const userManager = createUserManager();

	const handleSignOut = React.useCallback(async (): Promise<void> => {
		try {
			// Cleanup browser storage, otherwise the user will be automatically signed in again after redirect
			await userManager.storeUser(null);
			const logoutUri = getAppUrl().toString();
			const redirectTo = new URL("/logout", import.meta.env.VITE_COGNITO_DOMAIN);
			redirectTo.searchParams.append("client_id", import.meta.env.VITE_COGNITO_CLIENT_ID!);
			redirectTo.searchParams.append("logout_uri", logoutUri);
			globalThis.location.href = redirectTo.toString();
		} catch (error) {
			logger.error("Sign out error", error);
			toast.error("Something went wrong, unable to sign out");
		}
	}, [userManager]);

	return (
		<MenuItem component="div" onClick={handleSignOut} sx={{ justifyContent: "center" }}>
			Sign out
		</MenuItem>
	);
}
