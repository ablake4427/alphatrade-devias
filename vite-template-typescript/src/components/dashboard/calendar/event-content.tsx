import * as React from "react";
import type { EventContentArg } from "@fullcalendar/core";
import Box from "@mui/material/Box";
import Typography from "@mui/material/Typography";

import { dayjs } from "@/lib/dayjs";

export interface EventContentProps extends EventContentArg {
	event: EventContentArg["event"] & { extendedProps: { description?: string; priority?: "high" | "medium" | "low" } };
}

export function EventContent(arg: EventContentProps): React.JSX.Element {
	const { priority = "low" } = arg.event.extendedProps;

	const color =
		priority === "high"
			? "var(--mui-palette-error-main)"
			: priority === "medium"
				? "var(--mui-palette-warning-main)"
				: "transparent";

	const startTime = arg.event.start ? dayjs(arg.event.start).format("h:mm A") : null;
	const endTime = arg.event.end ? dayjs(arg.event.end).format("h:mm A") : null;

	const inline = arg.event.start && arg.event.end && dayjs(arg.event.end).diff(dayjs(arg.event.start), "minute") < 30;

	return (
		<React.Fragment>
			<Box
				sx={{
					backgroundColor: color,
					height: "100%",
					left: 0,
					position: "absolute",
					top: 0,
					width: "4px",
				}}
			/>
			<Box
				sx={{
					display: "flex",
					flexDirection: inline ? "row" : "column",
					gap: 1,
					pl: "8px",
					position: "sticky",
					top: 0,
				}}
			>
				{arg.event.allDay ? null : (
					<Typography sx={{ whiteSpace: "nowrap" }} variant="body2">
						{startTime} - {endTime}
					</Typography>
				)}
				<Typography noWrap variant="body2">
					{arg.event.title}
				</Typography>
			</Box>
		</React.Fragment>
	);
}
