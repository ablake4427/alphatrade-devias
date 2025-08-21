"use client";

import * as React from "react";
import { Auth0Provider } from "@auth0/auth0-react";
import { ClerkProvider } from "@clerk/clerk-react";
import { Helmet, HelmetProvider } from "react-helmet-async";

import "@/styles/global.css";

import type { Metadata } from "@/types/metadata";
import { appConfig } from "@/config/app";
import { AuthStrategy } from "@/lib/auth-strategy";
import { providerProps as auth0ProviderProps } from "@/lib/auth0/config";
import { providerProps as clerkProviderProps } from "@/lib/clerk/config";
import { getSettings as getPersistedSettings } from "@/lib/settings";
import { AuthProvider as CognitoProvider } from "@/components/auth/cognito/auth-context";
import { AuthProvider as CustomAuthProvider } from "@/components/auth/custom/auth-context";
import { AuthProvider as SupabaseProvider } from "@/components/auth/supabase/auth-context";
import { ThemeProvider } from "@/components/core//theme-provider";
import { Analytics } from "@/components/core/analytics";
import { I18nProvider } from "@/components/core/i18n-provider";
import { LocalizationProvider } from "@/components/core/localization-provider";
import { Rtl } from "@/components/core/rtl";
import { SettingsButton } from "@/components/core/settings/settings-button";
import { SettingsProvider } from "@/components/core/settings/settings-context";
import { Toaster } from "@/components/core/toaster";

const metadata = { title: appConfig.name } satisfies Metadata;

// Define the AuthProvider based on the selected auth strategy
// Remove this block if you are not using any auth strategy

let AuthProvider: React.FC<React.PropsWithChildren> = React.Fragment;

if (appConfig.authStrategy === AuthStrategy.AUTH0) {
	AuthProvider = function AuthProvider(props: React.PropsWithChildren): React.JSX.Element {
		return <Auth0Provider {...auth0ProviderProps} {...props} />;
	};
}

if (appConfig.authStrategy === AuthStrategy.CLERK) {
	AuthProvider = function AuthProvider(props: React.PropsWithChildren) {
		return <ClerkProvider {...clerkProviderProps} {...props} />;
	};
}

if (appConfig.authStrategy === AuthStrategy.COGNITO) {
	AuthProvider = CognitoProvider as React.FC<React.PropsWithChildren>;
}

if (appConfig.authStrategy === AuthStrategy.CUSTOM) {
	AuthProvider = CustomAuthProvider as React.FC<React.PropsWithChildren>;
}

if (appConfig.authStrategy === AuthStrategy.SUPABASE) {
	AuthProvider = SupabaseProvider as React.FC<React.PropsWithChildren>;
}

export interface RootProps {
	children: React.ReactNode;
}

export function Root({ children }: RootProps): React.JSX.Element {
	const settings = getPersistedSettings();

	return (
		<HelmetProvider>
			<Helmet>
				<title>{metadata.title}</title>
				<meta content={appConfig.themeColor} name="theme-color" />
			</Helmet>
			<AuthProvider>
				<Analytics>
					<LocalizationProvider>
						<SettingsProvider settings={settings}>
							<I18nProvider>
								<Rtl>
									<ThemeProvider>
										{children}
										<SettingsButton />
										<Toaster position="bottom-right" />
									</ThemeProvider>
								</Rtl>
							</I18nProvider>
						</SettingsProvider>
					</LocalizationProvider>
				</Analytics>
			</AuthProvider>
		</HelmetProvider>
	);
}
