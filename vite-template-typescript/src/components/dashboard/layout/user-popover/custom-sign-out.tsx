"use client";

import * as React from "react";
import MenuItem from "@mui/material/MenuItem";

import { authClient } from "@/lib/custom-auth/client";
import { logger } from "@/lib/default-logger";
import { useAuth } from "@/components/auth/custom/auth-context";
import { toast } from "@/components/core/toaster";

export function CustomSignOut(): React.JSX.Element {
	const auth = useAuth();

	const handleSignOut = React.useCallback(async (): Promise<void> => {
		try {
			const { error } = await authClient.signOut();

			if (error) {
				logger.error("Sign out error", error);
				toast.error("Something went wrong, unable to sign out");
				return;
			}

			await authClient.signOut();
			auth.setUser(null);
		} catch (error) {
			logger.error("Sign out error", error);
			toast.error("Something went wrong, unable to sign out");
		}
	}, [auth]);

	return (
		<MenuItem component="div" onClick={handleSignOut} sx={{ justifyContent: "center" }}>
			Sign out
		</MenuItem>
	);
}
