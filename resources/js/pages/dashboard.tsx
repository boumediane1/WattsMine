import AppLayout from '@/layouts/app-layout';
import CustomNode from '@/pages/CustomNode';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/react';
import { Background, Edge, MarkerType, Node, NodeTypes, ReactFlow } from 'reactflow';
import 'reactflow/dist/style.css';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
    },
];

const nodes: Node<{ title: string; value: string; image: string }>[] = [
    {
        id: 'solar',
        position: { x: 125, y: 0 },
        data: {
            title: 'Solar Production',
            value: '2351',
            image: '/images/solar-panel.png',
        },
        type: 'custom',
    },
    {
        id: 'grid',
        position: { x: 0, y: 100 },
        data: { title: 'Grid', value: '24', image: '/images/electricity.png' },
        type: 'custom',
    },
    {
        id: 'battery',
        position: { x: 250, y: 100 },
        data: { title: 'Battery', value: '3700', image: '/images/full-battery.png' },
        type: 'custom',
    },
    {
        id: 'home',
        position: { x: 125, y: 200 },
        data: { title: 'Home', value: '5709', image: '/images/business.png' },
        type: 'custom',
    },
];

const edges: Edge[] = [
    {
        id: 's->h',
        source: 'solar',
        target: 'home',
        animated: true,
    },
    { id: 'g->h', source: 'grid', target: 'home', animated: true, label: 'Consuming', markerEnd: { type: MarkerType.Arrow } },
    { id: 'b->h', source: 'battery', target: 'home', animated: true, label: 'Discharging', markerEnd: { type: MarkerType.Arrow } },
];

const nodeTypes: NodeTypes = {
    custom: CustomNode,
};

export default function Dashboard() {
    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Dashboard" />
            <div className="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
                <div style={{ width: '600px', height: '600px' }}>
                    <ReactFlow
                        nodes={nodes}
                        edges={edges}
                        fitView
                        nodeTypes={nodeTypes}
                        panOnDrag={false}
                        zoomOnScroll={false}
                        zoomOnDoubleClick={false}
                        nodesConnectable={false}
                        zoomOnPinch={false}
                        proOptions={{ hideAttribution: true }}
                    >
                        <Background />
                    </ReactFlow>
                </div>
            </div>
        </AppLayout>
    );
}
