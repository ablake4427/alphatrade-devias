"use client";

import * as React from "react";
import { useClerk, useUser } from "@clerk/clerk-react";

import { paths } from "@/paths";
import { logger } from "@/lib/default-logger";
import { getAppUrl } from "@/lib/get-app-url";

export interface AuthGuardProps {
	children: React.ReactNode;
}

export function AuthGuard({ children }: AuthGuardProps): React.JSX.Element | null {
	const { isLoaded, isSignedIn } = useUser();
	const clerk = useClerk();

	React.useEffect(() => {
		if (!isLoaded || isSignedIn) {
			return;
		}

		logger.debug("[AuthGuard] User is not authenticated, redirecting to sign in");

		clerk.redirectToSignIn({
			redirectUrl: new URL(paths.dashboard.overview, getAppUrl()).toString(),
		});
	}, [isSignedIn, isLoaded, clerk]);

	if (!isLoaded || !isSignedIn) {
		return null;
	}

	return <React.Fragment>{children}</React.Fragment>;
}
