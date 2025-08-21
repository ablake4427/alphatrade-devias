"use client";

import * as React from "react";

import { appConfig } from "@/config/app";
import { AuthStrategy } from "@/lib/auth-strategy";

import { AuthGuard as Auth0AuthGuard } from "./auth0/auth-guard";
import { AuthGuard as ClerkAuthGuard } from "./clerk/auth-guard";
import { AuthGuard as CognitoAuthGuard } from "./cognito/auth-guard";
import { AuthGuard as CustomAuthGuard } from "./custom/auth-guard";
import { AuthGuard as SupabaseAuthGuard } from "./supabase/auth-guard";

export interface AuthGuardProps {
	children: React.ReactNode;
}

export function AuthGuard(props: AuthGuardProps): React.JSX.Element {
	if (appConfig.authStrategy === AuthStrategy.AUTH0) {
		return <Auth0AuthGuard {...props} />;
	}

	if (appConfig.authStrategy === AuthStrategy.CLERK) {
		return <ClerkAuthGuard {...props} />;
	}

	if (appConfig.authStrategy === AuthStrategy.COGNITO) {
		return <CognitoAuthGuard {...props} />;
	}

	if (appConfig.authStrategy === AuthStrategy.CUSTOM) {
		return <CustomAuthGuard {...props} />;
	}

	if (appConfig.authStrategy === AuthStrategy.SUPABASE) {
		return <SupabaseAuthGuard {...props} />;
	}

	return <React.Fragment {...props} />;
}
