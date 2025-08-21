import type { Auth0ProviderOptions } from "@auth0/auth0-react";

import { paths } from "@/paths";
import { getAppUrl } from "@/lib/get-app-url";

export const providerProps: Auth0ProviderOptions = {
	cacheLocation: "localstorage",
	clientId: import.meta.env.VITE_AUTH0_CLIENT_ID!,
	domain: import.meta.env.VITE_AUTH0_DOMAIN!,
	authorizationParams: {
		redirect_uri: new URL(paths.auth.auth0.callback, getAppUrl()).toString(),
	},
	skipRedirectCallback: true,
};
