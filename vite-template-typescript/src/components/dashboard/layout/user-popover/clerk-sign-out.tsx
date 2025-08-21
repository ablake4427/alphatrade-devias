import * as React from "react";
import { useClerk } from "@clerk/clerk-react";
import MenuItem from "@mui/material/MenuItem";

import { logger } from "@/lib/default-logger";
import { getAppUrl } from "@/lib/get-app-url";
import { toast } from "@/components/core/toaster";

export function ClerkSignOut(): React.JSX.Element {
	const clerk = useClerk();

	const handleSignOut = React.useCallback(async (): Promise<void> => {
		try {
			await clerk.signOut({
				redirectUrl: getAppUrl().toString(),
			});
		} catch (error) {
			logger.error("Sign out error", error);
			toast.error("Something went wrong, unable to sign out");
		}
	}, [clerk]);

	return (
		<MenuItem onClick={handleSignOut} sx={{ justifyContent: "center" }}>
			Sign out
		</MenuItem>
	);
}
