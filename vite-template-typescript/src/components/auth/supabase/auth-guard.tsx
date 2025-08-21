"use client";

import * as React from "react";
import { useNavigate } from "react-router-dom";

import { paths } from "@/paths";
import { logger } from "@/lib/default-logger";
import { useAuth } from "@/components/auth/supabase/auth-context";

export interface AuthGuardProps {
	children: React.ReactNode;
}

export function AuthGuard({ children }: AuthGuardProps): React.JSX.Element | null {
	const { isAuthenticated, isLoading } = useAuth();
	const navigate = useNavigate();

	React.useEffect(() => {
		if (isLoading || isAuthenticated) {
			return;
		}

		logger.debug("[AuthGuard] User is not authenticated, redirecting to sign in");

		navigate(paths.auth.supabase.signIn);
	}, [isAuthenticated, isLoading, navigate]);

	if (isLoading || !isAuthenticated) {
		return null;
	}

	return <React.Fragment>{children}</React.Fragment>;
}
