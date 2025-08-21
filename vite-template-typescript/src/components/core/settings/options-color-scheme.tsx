"use client";

import type * as React from "react";
import InputLabel from "@mui/material/InputLabel";
import Stack from "@mui/material/Stack";
import { Devices as DevicesIcon } from "@phosphor-icons/react/dist/ssr/Devices";
import { Moon as MoonIcon } from "@phosphor-icons/react/dist/ssr/Moon";
import { Sun as SunIcon } from "@phosphor-icons/react/dist/ssr/Sun";

import type { Mode } from "@/styles/theme/types";

import { Option } from "./option";

export interface OptionsColorSchemeProps {
	onChange?: (value: Mode) => void;
	value?: Mode;
}

export function OptionsColorScheme({ onChange, value }: OptionsColorSchemeProps): React.JSX.Element {
	return (
		<Stack spacing={1}>
			<InputLabel>Color scheme</InputLabel>
			<Stack direction="row" spacing={2} sx={{ alignItems: "center", flexWrap: "wrap" }}>
				{(
					[
						{ label: "Light", value: "light", icon: <SunIcon /> },
						{ label: "Dark", value: "dark", icon: <MoonIcon /> },
						{ label: "System", value: "system", icon: <DevicesIcon /> },
					] satisfies { label: string; value: string; icon: React.ReactNode }[]
				).map((option) => (
					<Option
						icon={option.icon}
						key={option.value}
						label={option.label}
						onClick={() => {
							onChange?.(option.value as Mode);
						}}
						selected={option.value === value}
					/>
				))}
			</Stack>
		</Stack>
	);
}
