import * as React from "react";
import Box from "@mui/material/Box";
import Button from "@mui/material/Button";
import Card from "@mui/material/Card";
import Divider from "@mui/material/Divider";
import Stack from "@mui/material/Stack";
import Typography from "@mui/material/Typography";
import { Plus as PlusIcon } from "@phosphor-icons/react/dist/ssr/Plus";
import { Helmet } from "react-helmet-async";
import { useSearchParams } from "react-router-dom";

import type { Metadata } from "@/types/metadata";
import { appConfig } from "@/config/app";
import { useMutation, useQuery, useQueryClient } from "@tanstack/react-query";

import { OrderModal } from "@/components/dashboard/order/order-modal";
import { OrdersFilters } from "@/components/dashboard/order/orders-filters";
import type { Filters } from "@/components/dashboard/order/orders-filters";
import { OrdersPagination } from "@/components/dashboard/order/orders-pagination";
import { OrdersSelectionProvider } from "@/components/dashboard/order/orders-selection-context";
import { OrdersTable } from "@/components/dashboard/order/orders-table";
import type { Order } from "@/components/dashboard/order/orders-table";
import { getOpenOrders, getOrderHistory, updateOrderStatus } from "@/api/orders";

const metadata = { title: `List | Orders | Dashboard | ${appConfig.name}` } satisfies Metadata;

export function Page(): React.JSX.Element {
        const { customer, id, previewId, sortDir, status } = useExtractSearchParams();
        const queryClient = useQueryClient();

        const { data: openOrders = [] } = useQuery({
                queryKey: ["orders", "open"],
                queryFn: getOpenOrders,
        });
        const { data: historyOrders = [] } = useQuery({
                queryKey: ["orders", "history"],
                queryFn: getOrderHistory,
        });

        const mutation = useMutation({
                mutationFn: ({ id, status }: { id: string; status: Order["status"] }) =>
                        updateOrderStatus(id, status),
                onSuccess: () => {
                        queryClient.invalidateQueries({ queryKey: ["orders"] });
                },
        });

        const orders = React.useMemo(() => [...openOrders, ...historyOrders], [
                openOrders,
                historyOrders,
        ]);

        const sortedOrders = applySort([...orders], sortDir);
        const filteredOrders = applyFilters(sortedOrders, { customer, id, status });

        return (
                <React.Fragment>
                        <Helmet>
                                <title>{metadata.title}</title>
                        </Helmet>
                        <Box
                                sx={{
                                        maxWidth: "var(--Content-maxWidth)",
                                        m: "var(--Content-margin)",
                                        p: "var(--Content-padding)",
                                        width: "var(--Content-width)",
                                }}
                        >
                                <Stack spacing={4}>
                                        <Stack direction={{ xs: "column", sm: "row" }} spacing={3} sx={{ alignItems: "flex-start" }}>
                                                <Box sx={{ flex: "1 1 auto" }}>
                                                        <Typography variant="h4">Orders</Typography>
                                                </Box>
                                                <div>
                                                        <Button startIcon={<PlusIcon />} variant="contained">
                                                                Add
                                                        </Button>
                                                </div>
                                        </Stack>
                                        <OrdersSelectionProvider orders={filteredOrders}>
                                                <Card>
                                                        <OrdersFilters filters={{ customer, id, status }} sortDir={sortDir} />
                                                        <Divider />
                                                        <Box sx={{ overflowX: "auto" }}>
                                                                <OrdersTable
                                                                        rows={filteredOrders}
                                                                        onStatusChange={(orderId, newStatus) =>
                                                                                mutation.mutate({ id: orderId, status: newStatus })
                                                                        }
                                                                />
                                                        </Box>
                                                        <Divider />
                                                        <OrdersPagination count={filteredOrders.length} page={0} />
                                                </Card>
                                        </OrdersSelectionProvider>
                                </Stack>
                        </Box>
                        <OrderModal open={Boolean(previewId)} orderId={previewId} />
                </React.Fragment>
        );
}

function useExtractSearchParams(): {
	customer?: string;
	id?: string;
	previewId?: string;
	sortDir?: "asc" | "desc";
	status?: string;
} {
	const [searchParams] = useSearchParams();

	return {
		customer: searchParams.get("customer") || undefined,
		id: searchParams.get("id") || undefined,
		previewId: searchParams.get("previewId") || undefined,
		sortDir: (searchParams.get("sortDir") || undefined) as "asc" | "desc" | undefined,
		status: searchParams.get("status") || undefined,
	};
}

// Sorting and filtering has to be done on the server.

function applySort(row: Order[], sortDir: "asc" | "desc" | undefined): Order[] {
	return row.sort((a, b) => {
		if (sortDir === "asc") {
			return a.createdAt.getTime() - b.createdAt.getTime();
		}

		return b.createdAt.getTime() - a.createdAt.getTime();
	});
}

function applyFilters(row: Order[], { customer, id, status }: Filters): Order[] {
	return row.filter((item) => {
		if (customer && !item.customer?.name?.toLowerCase().includes(customer.toLowerCase())) {
			return false;
		}

		if (id && !item.id?.toLowerCase().includes(id.toLowerCase())) {
			return false;
		}

		if (status && item.status !== status) {
			return false;
		}

		return true;
	});
}
