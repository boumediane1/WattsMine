import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Progress } from '@/components/ui/progress';
import AppLayout from '@/layouts/app-layout';
import { type BreadcrumbItem, Reading } from '@/types';
import { Head, usePage } from '@inertiajs/react';
import { useEcho } from '@laravel/echo-react';
import { useState } from 'react';
import 'reactflow/dist/style.css';
import { ChartBarDefault } from './ProductionChart';
import SolarPVSystem from './SolarPVSystem';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
    },
];

export type Props = {
    data: {
        production: {
            hour: number;
            active_power: number;
        }[];
        consumption: {
            hour: number;
            active_power: number;
        }[];
    };
};

const sum = (readings: Reading[]) => readings.map((i) => i.active_power).reduce((accumulator, current) => accumulator + current);

export default function Dashboard() {
    const { props } = usePage<Props>();

    const [distribution, setDistribution] = useState({
        production: 0,
        consumption: 0,
        utility_grid: 0,
    });

    useEcho('power', 'ReadingsSimulated', (e: { readings: Reading[] }) => {
        setDistribution({
            production: sum(e.readings.filter((i) => i.type === 'production')),
            consumption: sum(e.readings.filter((i) => i.type === 'consumption')),
            utility_grid: sum(e.readings.filter((i) => i.type === 'utility_grid')),
        });
    }).listen();

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Dashboard" />

            <div className="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
                <div className="flex items-center justify-between">
                    <h2 className="text-xl font-semibold tracking-tight">Dashboard</h2>

                    <div className="flex items-center gap-4">
                        <div className="flex items-center gap-2">
                            <Label htmlFor="from">From</Label>
                            <Input id="from" type="date" />
                        </div>

                        <div className="flex items-center gap-2">
                            <Label htmlFor="to">To</Label>
                            <Input id="to" type="date" />
                        </div>

                        <Button>Export</Button>
                    </div>
                </div>

                <div className="grid grid-cols-3 gap-4">
                    <div className="col-span-2">
                        <ChartBarDefault data={props.data} />
                    </div>

                    <div className="col-span-1 flex flex-col gap-4">
                        <Card>
                            <CardHeader>
                                <CardTitle className="capitalize">Battery usage</CardTitle>
                            </CardHeader>

                            <CardContent className="space-y-4">
                                <div className="flex items-center justify-between gap-x-4">
                                    <div className="text-3xl font-bold" style={{ fontFamily: 'Inter, sand-serif' }}>
                                        0%
                                    </div>
                                    <div className="text-sm text-gray-500">0 hours remaining</div>
                                </div>

                                <Progress value={0} className="h-4 rounded bg-red-500/20" />
                            </CardContent>
                        </Card>

                        <Card className="h-full">
                            <CardHeader>
                                <CardTitle className="capitalize">Power distribution</CardTitle>
                                <CardDescription>Now</CardDescription>
                            </CardHeader>

                            <CardContent className="h-full">
                                <SolarPVSystem distribution={distribution} />
                            </CardContent>
                        </Card>
                    </div>
                </div>
            </div>
        </AppLayout>
    );
}
