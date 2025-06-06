'use client';

import { CartesianGrid, Line, LineChart, XAxis, YAxis } from 'recharts';

import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { ChartConfig, ChartContainer, ChartTooltip, ChartTooltipContent } from '@/components/ui/chart';
import { History } from '@/types';

export const description = 'A multiple line chart';

const chartData = [
    { month: 'January', desktop: 186, mobile: 80 },
    { month: 'February', desktop: 305, mobile: 200 },
    { month: 'March', desktop: 237, mobile: 120 },
    { month: 'April', desktop: 73, mobile: 190 },
    { month: 'May', desktop: 209, mobile: 130 },
    { month: 'June', desktop: 214, mobile: 140 },
];

const chartConfig = {
    desktop: {
        label: 'Desktop',
        color: 'var(--chart-1)',
    },
    mobile: {
        label: 'Mobile',
        color: 'var(--chart-2)',
    },
} satisfies ChartConfig;

export function ChartLineMultiple({ data }: { data: History[] }) {
    console.log(data);

    return (
        <Card>
            <CardHeader>
                <CardTitle>Line Chart - Multiple</CardTitle>
                <CardDescription>January - June 2024</CardDescription>
            </CardHeader>
            <CardContent>
                <ChartContainer config={chartConfig}>
                    <LineChart
                        accessibilityLayer
                        data={data}
                        margin={{
                            left: 12,
                            right: 12,
                        }}
                    >
                        <CartesianGrid vertical={false} />
                        <YAxis domain={['auto', 'auto']} />
                        <XAxis dataKey="second" tickLine={false} axisLine={false} tickMargin={8} tickFormatter={(value) => value} />
                        <ChartTooltip cursor={false} content={<ChartTooltipContent />} />
                        <Line dataKey="production" type="monotone" stroke="var(--color-desktop)" strokeWidth={2} dot={false} />
                        {/*<Line dataKey="consumption" type="monotone" stroke="var(--color-mobile)" strokeWidth={2} dot={false} />*/}
                    </LineChart>
                </ChartContainer>
            </CardContent>
        </Card>
    );
}
