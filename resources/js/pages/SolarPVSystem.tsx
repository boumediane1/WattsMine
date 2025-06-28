import CustomNode from '@/pages/CustomNode';
import ReactFlow, { Edge, MarkerType, Node, NodeTypes } from 'reactflow';

const edges: Edge[] = [
    {
        id: 's->h',
        source: 'production',
        target: 'consumption',
        animated: true,
    },
    {
        id: 'g->h',
        source: 'utility_grid',
        target: 'consumption',
        animated: true,
        label: 'Consuming',
        markerEnd: { type: MarkerType.Arrow },
    },
    {
        id: 'b->h',
        source: 'battery',
        target: 'consumption',
        animated: false,
        markerEnd: { type: MarkerType.Arrow },
        style: {
            strokeDasharray: '5,5',
        },
    },
];

const nodeTypes: NodeTypes = {
    custom: CustomNode,
};
const SolarPVSystem = ({ distribution }: { distribution: { production: number; consumption: number; utility_grid: number } }) => {
    const nodes: Node<{ title: string; value: number; image: string }>[] = [
        {
            id: 'production',
            position: { x: 125, y: 0 },
            data: {
                title: 'Solar Production',
                value: distribution.production,
                image: '/images/solar-panel.png',
            },
            type: 'custom',
        },
        {
            id: 'utility_grid',
            position: { x: 0, y: 100 },
            data: { title: 'Grid', value: distribution.utility_grid, image: '/images/electricity.png' },
            type: 'custom',
        },
        {
            id: 'battery',
            position: { x: 250, y: 100 },
            data: { title: 'Battery', value: 0, image: '/images/battery.png' },
            type: 'custom',
        },
        {
            id: 'consumption',
            position: { x: 125, y: 200 },
            data: { title: 'Home', value: distribution.consumption, image: '/images/business.png' },
            type: 'custom',
        },
    ];

    return (
        <ReactFlow
            className="m-0 p-0"
            nodes={nodes}
            edges={edges}
            fitView
            fitViewOptions={{
                padding: 0,
            }}
            nodeTypes={nodeTypes}
            panOnDrag={false}
            zoomOnScroll={false}
            zoomOnDoubleClick={false}
            nodesConnectable={false}
            zoomOnPinch={false}
            proOptions={{ hideAttribution: true }}
        />
    );
};

export default SolarPVSystem;
