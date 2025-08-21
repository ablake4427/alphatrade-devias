import type { ClerkProviderProps } from "@clerk/clerk-react";

import { getAppUrl } from "@/lib/get-app-url";

export const providerProps = {
	publishableKey: import.meta.env.VITE_CLERK_PUBLISHABLE_KEY!,
	afterSignOutUrl: getAppUrl().toString(),
} as ClerkProviderProps;
