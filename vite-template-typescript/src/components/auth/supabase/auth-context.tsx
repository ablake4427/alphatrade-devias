"use client";

import * as React from "react";
import type { SupabaseClient, User } from "@supabase/supabase-js";

import { createClient as createSupabaseClient } from "@/lib/supabase/client";

type AuthState = {
	isAuthenticated: boolean;
	isLoading: boolean;
	user: User | null;
};

export const AuthContext = React.createContext<AuthState>({
	isAuthenticated: false,
	isLoading: true,
	user: null,
});

export interface AuthProviderProps {
	children: React.ReactNode;
}

export const AuthProvider = ({ children }: AuthProviderProps): React.JSX.Element => {
	const [supabaseClient] = React.useState<SupabaseClient>(createSupabaseClient());

	const [state, setState] = React.useState<AuthState>({
		isAuthenticated: false,
		isLoading: true,
		user: null,
	});

	React.useEffect(() => {
		const {
			data: { subscription },
		} = supabaseClient.auth.onAuthStateChange((_, session) => {
			setState((prevState) => ({
				...prevState,
				isAuthenticated: Boolean(session?.user),
				isLoading: false,
				user: session?.user ?? null,
			}));
		});

		return () => {
			subscription.unsubscribe();
		};
	}, [supabaseClient]);

	return <AuthContext.Provider value={{ ...state }}>{children}</AuthContext.Provider>;
};

export function useAuth(): AuthState {
	const context = React.useContext(AuthContext);

	if (context === undefined) {
		throw new Error("useAuth must be used within an AuthProvider");
	}

	return context;
}
