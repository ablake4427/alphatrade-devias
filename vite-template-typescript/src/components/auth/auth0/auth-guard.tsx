"use client";

import * as React from "react";
import { useAuth0 } from "@auth0/auth0-react";

import { logger } from "@/lib/default-logger";

export interface AuthGuardProps {
	children: React.ReactNode;
}

export function AuthGuard({ children }: AuthGuardProps): React.JSX.Element | null {
	const { isAuthenticated, isLoading, loginWithRedirect } = useAuth0();

	React.useEffect(() => {
		if (isLoading || isAuthenticated) {
			return;
		}

		logger.debug("[AuthGuard] User is not authenticated, redirecting to sign in");

		loginWithRedirect();
	}, [isAuthenticated, isLoading, loginWithRedirect]);

	if (isLoading || !isAuthenticated) {
		return null;
	}

	return <React.Fragment>{children}</React.Fragment>;
}
