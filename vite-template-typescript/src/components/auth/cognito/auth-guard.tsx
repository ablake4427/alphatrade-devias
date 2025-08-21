"use client";

import * as React from "react";
import { useAuth } from "react-oidc-context";

import { logger } from "@/lib/default-logger";

export interface AuthGuardProps {
	children: React.ReactNode;
}

export function AuthGuard({ children }: AuthGuardProps): React.JSX.Element | null {
	const { isAuthenticated, isLoading, signinRedirect } = useAuth();

	React.useEffect(() => {
		if (isLoading || isAuthenticated) {
			return;
		}

		logger.debug("[AuthGuard] User is not authenticated, redirecting to sign in");

		signinRedirect();
	}, [isAuthenticated, isLoading, signinRedirect]);

	if (isLoading || !isAuthenticated) {
		return null;
	}

	return <React.Fragment>{children}</React.Fragment>;
}
