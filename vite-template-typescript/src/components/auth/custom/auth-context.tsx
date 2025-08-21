"use client";

import * as React from "react";

import { authClient, type User } from "@/lib/custom-auth/client";

type AuthState = {
	isAuthenticated: boolean;
	isLoading: boolean;
	user: User | null;
};

export const AuthContext = React.createContext<
	AuthState & {
		setUser: (user: User | null) => void;
	}
>({
	isAuthenticated: false,
	isLoading: true,
	user: null,
	setUser: () => {},
});

export interface AuthProviderProps {
	children: React.ReactNode;
}

export const AuthProvider = ({ children }: AuthProviderProps): React.JSX.Element => {
	const [state, setState] = React.useState<AuthState>({
		isAuthenticated: false,
		isLoading: true,
		user: null,
	});

	React.useEffect(() => {
		const initialize = async (): Promise<void> => {
			const { data } = await authClient.getUser();

			setState((prevState) => ({
				...prevState,
				isAuthenticated: Boolean(data?.user),
				isLoading: false,
				user: data?.user ?? null,
			}));
		};

		initialize();
	}, []);

	const setUser = (user: User | null): void => {
		setState((prevState) => ({ ...prevState, isAuthenticated: Boolean(user), user }));
	};

	return (
		<AuthContext.Provider
			value={{
				...state,
				setUser,
			}}
		>
			{children}
		</AuthContext.Provider>
	);
};

export function useAuth(): AuthState & { setUser: (user: User | null) => void } {
	const context = React.useContext(AuthContext);

	if (context === undefined) {
		throw new Error("useAuth must be used within an AuthProvider");
	}

	return context;
}
