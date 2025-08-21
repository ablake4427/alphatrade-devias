"use client";

import type * as React from "react";
import { AuthProvider as OidcProvider } from "react-oidc-context";

import { createUserManager } from "@/lib/cognito/user-manager";

export interface AuthProviderProps {
	children: React.ReactNode;
}

export function AuthProvider(props: AuthProviderProps): React.ReactElement {
	const userManager = createUserManager();

	return <OidcProvider userManager={userManager} skipSigninCallback {...props} />;
}
