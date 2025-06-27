import AppLayout from '@/layouts/app-layout';
import { DataTableDemo } from '@/pages/Datatable';
import type { BreadcrumbItem, Reading } from '@/types';
import { Head } from '@inertiajs/react';
import { useEcho } from '@laravel/echo-react';
import { useState } from 'react';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Brakers',
        href: '/brakers',
    },
];

const Breakers = () => {
    const [readings, setReadings] = useState<Reading[]>([]);

    useEcho('power', 'ReadingsSimulated', (e: { readings: Reading[] }) => {
        setReadings(e.readings.filter((i) => i.type !== 'utility_grid'));
    });

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Brakers" />
            <div className="flex h-full flex-1 flex-col gap-4 rounded-lg p-4">
                <h2 className="text-xl font-semibold tracking-tight">Brakers</h2>
                <DataTableDemo readings={readings} />
            </div>
        </AppLayout>
    );
};

export default Breakers;
