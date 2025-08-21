"use client";

// NOTE: This is a simple in-memory implementation of an authentication client.
//  It is used to demonstrate how to create a custom authentication client to connect to your API.

function generateToken(): string {
	const arr = new Uint8Array(12);
	globalThis.crypto.getRandomValues(arr);
	return Array.from(arr, (v) => v.toString(16).padStart(2, "0")).join("");
}

export interface User {
	id: string;
	name?: string;
	avatar?: string;
	email?: string;

	[key: string]: unknown;
}

const user = {
	id: "USR-000",
	avatar: "/assets/avatar.png",
	firstName: "Sofia",
	lastName: "Rivers",
	email: "sofia@devias.io",
} satisfies User;

export interface SignUpParams {
	firstName: string;
	lastName: string;
	email: string;
	password: string;
}

export interface SignInWithOAuthParams {
	provider: "google" | "discord";
}

export interface SignInWithPasswordParams {
	email: string;
	password: string;
}

export interface ResetPasswordParams {
	email: string;
}

class AuthClient {
	async signUp(_: SignUpParams): Promise<{ data?: { user: User }; error?: string }> {
		const token = generateToken();
		localStorage.setItem("access_token", token);

		return { data: { user } };
	}

	async signInWithOAuth(_: SignInWithOAuthParams): Promise<{ error?: string }> {
		return { error: "Social authentication not implemented" };
	}

	async signInWithPassword(params: SignInWithPasswordParams): Promise<{
		data?: { user: User };
		error?: string;
	}> {
		const { email, password } = params;

		if (email !== "sofia@devias.io" || password !== "Secret1") {
			return { error: "Invalid credentials" };
		}

		const token = generateToken();
		localStorage.setItem("access_token", token);

		return { data: { user } };
	}

	async resetPassword(_: ResetPasswordParams): Promise<{ error?: string }> {
		return { error: "Password reset not implemented" };
	}

	async updatePassword(_: ResetPasswordParams): Promise<{ error?: string }> {
		return { error: "Update reset not implemented" };
	}

	async getUser(): Promise<{ data?: { user: User | null }; error?: string }> {
		const token = localStorage.getItem("access_token");

		if (!token) {
			return { data: { user: null } };
		}

		return { data: { user } };
	}

	async signOut(): Promise<{ error?: string }> {
		localStorage.removeItem("access_token");

		return {};
	}
}

export const authClient = new AuthClient();
