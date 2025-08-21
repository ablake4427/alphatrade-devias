import type { SupabaseClient } from "@supabase/supabase-js";
import { createClient as createBrowserClient } from "@supabase/supabase-js";

// NOTE: Currently we do not support PKCE flow in Vite version of the template.

// NOTE: Since this is loaded only on the browser, we can cache the client instance.
//  Why do I need to call this function every time I want to use the Supabase client?
//  Short answer, you don't need to, but you may not be using Supabase and this will throw an error if you don't
//  configure the Supabase credentials. So, to avoid that, we create the client only when we need it.

let client: SupabaseClient | undefined;

export function createClient(): SupabaseClient {
	if (client) {
		return client;
	}

	client = createBrowserClient(import.meta.env.VITE_SUPABASE_URL!, import.meta.env.VITE_SUPABASE_PUBLIC_KEY!);

	return client;
}
