import AppLayout from '@/layouts/app-layout';
import { DataTableDemo } from '@/pages/Datatable';
import type { BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/react';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Brakers',
        href: '/brakers',
    },
];

const Breakers = () => {
    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Brakers" />
            <div className="flex h-full flex-1 flex-col gap-4 rounded-lg p-4">
                <h2 className="text-xl font-semibold tracking-tight">Brakers</h2>
                <DataTableDemo />
            </div>
        </AppLayout>
    );
};

export default Breakers;
