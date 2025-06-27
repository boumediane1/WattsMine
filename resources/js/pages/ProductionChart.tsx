import { Bar, BarChart, CartesianGrid, XAxis, YAxis } from 'recharts';

import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { ChartConfig, ChartContainer, ChartTooltip, ChartTooltipContent } from '@/components/ui/chart';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { useState } from 'react';
import { Props } from './dashboard';

const chartConfig = {
    active_power: {
        label: 'Active power',
        color: 'var(--chart-5)',
    },
} satisfies ChartConfig;

export function ChartBarDefault({ data }: Props) {
    const [type, setType] = useState<'production' | 'consumption'>('production');

    return (
        <Card className="pt-0">
            <CardHeader className="flex items-center gap-2 space-y-0 border-b py-5 sm:flex-row">
                <div className="grid flex-1 gap-1">
                    <CardTitle className="capitalize">Power overview</CardTitle>
                    <CardDescription>Last 24 hours</CardDescription>
                </div>

                <Select defaultValue={'production'} onValueChange={(value: 'production' | 'consumption') => setType(value)}>
                    <SelectTrigger className="hidden w-[160px] rounded-lg sm:ml-auto sm:flex" aria-label="Select a value">
                        <SelectValue placeholder="Last 3 months" />
                    </SelectTrigger>
                    <SelectContent className="rounded-xl">
                        <SelectItem value="production" className="rounded-lg">
                            Production
                        </SelectItem>
                        <SelectItem value="utility_grid" className="rounded-lg">
                            Grid utility
                        </SelectItem>
                        <SelectItem value="consumption" className="rounded-lg">
                            Consumption
                        </SelectItem>
                    </SelectContent>
                </Select>
            </CardHeader>

            <CardContent>
                <ChartContainer config={chartConfig}>
                    <BarChart accessibilityLayer data={data[type]}>
                        <CartesianGrid vertical={false} />
                        <YAxis dataKey="active_power" />
                        <XAxis
                            dataKey="hour"
                            tickLine={false}
                            tickMargin={10}
                            axisLine={false}
                            tickFormatter={(value) => {
                                return `${value.toString().padStart(2, '0')}h`;
                            }}
                        />
                        <ChartTooltip cursor={false} content={<ChartTooltipContent hideLabel className="w-36" />} />
                        <Bar dataKey="active_power" fill="var(--color-active_power)" radius={8} />
                    </BarChart>
                </ChartContainer>
            </CardContent>
        </Card>
    );
}
